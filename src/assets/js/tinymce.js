tinymce.init({
    selector: 'textarea.tinyMCE',
    height: 300,
    plugins: 'advlist autolink link charmap preview searchreplace wordcount fullscreen insertdatetime table codesample lists',
    toolbar: 'styles | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link unlink | fullscreen preview',
    menubar: 'edit format tools table',
    content_style: 'body{font-family:Helvetica,Arial,sans-serif; font-size:16px}',
    setup: function (editor) {
        editor.on('init', function () {
            console.log('TinyMCE Initialized');
        });
    }
});
