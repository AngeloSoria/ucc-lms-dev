tinymce.init({
    selector: 'textarea.tinyMCE',
    height: 300,
    plugins: 'advlist autolink link charmap preview searchreplace wordcount fullscreen insertdatetime table codesample lists image link media mediaembed code image',
    toolbar: 'styles | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | fullscreen preview link code image media',
    menubar: 'edit format tools table',
    content_style: `
        body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 16px;
        }
        iframe {
            max-width: 100%;
            height: auto;
            aspect-ratio: 16 / 9;
            width: 100%;
        }
    `,
});

document.addEventListener('focusin', (e) => {
    if (e.target.closest(".tox-tinymce-aux, .moxman-window, .tam-assetmanager-root") !== null) {
        e.stopImmediatePropagation();
    }
});
