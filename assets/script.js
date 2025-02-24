jQuery(document).ready(function($) {
    // Inicializar CodeMirror para cada textarea
    $('.scriptify-editor').each(function() {
        CodeMirror.fromTextArea(this, {
            lineNumbers: true,
            mode: 'javascript',
            theme: 'default',
            tabSize: 2,
            extraKeys: {'Ctrl-Space': 'autocomplete'}
        });
    });
});