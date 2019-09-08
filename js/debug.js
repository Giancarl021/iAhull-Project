$(document).ready(() => {
    const $pre = $('pre');
    const text = $pre.text();

    $pre.html(text
        .replace(/[\[\]]/g, '')
        .replace(/\s*=>\s*/g, ': ')
        .replace(/(Array\n(\s)*\()/g, '{')
        .replace(/\)/g, '}')
    );
});