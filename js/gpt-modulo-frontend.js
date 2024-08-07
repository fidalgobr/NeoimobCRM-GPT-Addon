(async function() {
    const editor = document.querySelector('#wp-prop_des-wrap');
    const editorTabs = document.querySelector('.wp-editor-tabs');
    const editorTabsButtons = Array.from(editorTabs.children);
    const botaoIA = editorTabsButtons[1].cloneNode(true);
    const botaoNext = document.querySelector('.btn-next');

    const styleElement = document.createElement('style');
    const customCSS = `
        .ai-mode #mceu_26, .ai-mode #qt_prop_des_toolbar input {
            display: none;
        }

        .ai-mode #qt_prop_des_toolbar {
            white-space: pre;
        }

        .ai-mode #qt_prop_des_toolbar::before {
            content: 'Modo inteligência artificial. \\ASeu texto será gerado automaticamente após o preenchimento das informações sobre o imóvel. \\AUtilize o campo abaixo para inserir instruções adicionais.';
            display: block;
            font-size: 16px;
        }
    `;
    styleElement.innerHTML = customCSS;
    document.head.append(styleElement);

    botaoIA.innerText = 'Inteligência Artificial';
    botaoIA.id = 'prop_des-ai';
    botaoIA.classList.remove('switch-html');
    botaoIA.classList.add('switch-ai');

    function handleClickAI(event) {
        event.stopImmediatePropagation();

        const editorClassListArray = Array.from(editor.classList);

        this.setAttribute(
            'style',
            `
                background: #f6f7f7;
                color: #50575e;
                border-bottom-color: #f6f7f7;
            `
        );

        if (
            (editorClassListArray.includes('tmce-active') || editorClassListArray.includes('html-active')) &&
            !editorClassListArray.includes('ai-mode')
        ) {
            editor.classList.remove('tmce-active', 'html-active');
        }

        editor.classList.add('ai-mode');
        window.aiDescriptionMode = true;
    }

    function handleClickOtherButtons() {
        botaoIA.removeAttribute('style');

        editor.classList.remove('ai-mode');
        window.aiDescriptionMode = false;
    }

    editorTabsButtons.forEach(button => {
        button.addEventListener('click', handleClickOtherButtons);
    });

    botaoIA.addEventListener('click', handleClickAI);

    editorTabs.prepend(botaoIA);

    async function gerarDescricao() {
        const form = document.querySelector('#submit_property_form');
        const formInputs = form.querySelectorAll('input:not([type=button]):not([type=file])');
        const additionalInfoTextArea = document.querySelector('textarea');

        let infoSobreImovel = new Object();

        for (let input of formInputs) {
            if (input.name) {
                infoSobreImovel[input.name] = input.value;
            }
        }

        if (additionalInfoTextArea.value.length > 1) {
            infoSobreImovel.additionalInfo = additionalInfoTextArea.value;
        }

        let descricao;

        try {
            descricao = (
                await axios.post('/wp-json/chatgpt-neoimob/v1/gerar-descricao', infoSobreImovel)
            ).data.resposta;
        } catch (e) {
            console.error(e);
            alert('Houve um erro ao gerar a descrição com IA!');
            return;
        }

        additionalInfoTextArea.value = descricao;
    }

    function checkSeEstaNoUltimoPasso() {
        setTimeout(
            async() => {
                const estaNoUltimoPasso = document.querySelector('.btn-step-submit').getAttribute('style').length < 1;

                if (estaNoUltimoPasso && window.aiDescriptionMode) {
                    const botaoSubmit = document.querySelector('#add_new_property');

                    botaoSubmit.setAttribute('disabled', true);
                    await gerarDescricao();
                    botaoSubmit.removeAttribute('disabled');
                }
            }, 100);
    }

    botaoNext.addEventListener('click', checkSeEstaNoUltimoPasso);
})();