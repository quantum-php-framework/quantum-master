var snippets = document.querySelectorAll('.snippet');
[].forEach.call(snippets, function (snippet) {
    snippet.firstChild.insertAdjacentHTML('beforebegin', '<button class="btn" data-clipboard-snippet><img class="clippy" width="13" src="/static/templates/preadmin/plugins/clipboard/dist/clippy.svg" alt="Copy to clipboard"></button>');
});
var clipboardSnippets = new ClipboardJS('[data-clipboard-snippet]', {
    target: function (trigger) {
        return trigger.nextElementSibling;
    }
});
clipboardSnippets.on('success', function (e) {
    e.clearSelection();
    showTooltip(e.trigger, 'Copied!');
    console.log($(this));
    //this.target.fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100);
});
clipboardSnippets.on('error', function (e) {
    showTooltip(e.trigger, fallbackMessage(e.action));
});