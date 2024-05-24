wp.blocks.registerBlockType('scriptify/custom-block', {
    title: 'Scriptify Block',
    icon: 'universal-access-alt',
    category: 'common',
    edit: function() {
        return wp.element.createElement(
            'p',
            { className: 'custom-block' },
            'Scriptify Block Content'
        );
    },
    save: function() {
        return wp.element.createElement(
            'p',
            { className: 'custom-block' },
            'Scriptify Block Content'
        );
    },
});
