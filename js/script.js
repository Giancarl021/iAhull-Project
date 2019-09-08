let $modal, $modalSrcEl;

$(document).ready(() => {
    $modal = $('#modal-base');
    $modalSrcEl = $modal.children('#modal-header').children('#source-element');
    responsively();
});

$(window).resize(() => {
    responsively();
});

function changeQuestion(question, action) {
    const $question = $(question).children('.reaction-box');
    $.ajax({
        url: 'php/change_status.php',
        data: `filename=${$(question).attr('data-filename')}&action=${action}`, // GET para PHP
        statusCode: {
            400: _ => {
                _createToast('Erro: ' + _.statusText)
            }
        }
    }).done(_ => {
        if (_ === 'null') {
            _createToast('Você precisa estar logado para colocar sua opinião');
            return;
        } else if (_ === 'err') {
            _createToast('Erro ao requisitar PHP');
        }
        const data = _.split('&');
        $question.children('.likes-counter').text(data[0]);
        $question.children('.dislikes-counter').text(data[1]);
    });
}

function showAnswers(origin) {
    origin.innerText = origin.innerText === 'Ver respostas' ? 'Ocultar respostas' : 'Ver respostas';
    $(origin).parent().next().slideToggle('fast');
}

async function initModal(src, clone) {
    const user = await _getUser();
    if (user === ';') {
        _createToast('Você precisa estar logado para fazer uma pergunta');
        return;
    }
    $('body').css('overflow', 'hidden');
    $modal.fadeIn(500);
    if (clone) {
        $modal.children('#modal-section').css('height', '70%');
        const $src = $(src).clone();
        $src.children('a, .reaction-box').remove();
        $modalSrcEl.append($src);
    } else {
        $modal.children('#modal-section').css('height', '72%');
        $(`<div class="question-card"><h1 class="question-title center-advice equal-margin">${src}</h1></div>`).appendTo($modalSrcEl);
    }
    $(document).keydown(_ => {
        if (_.key === 'Escape') {
            closeModal();
        }
    });
}

function closeModal() {
    $('body').css('overflow', 'unset');
    $modal.fadeOut(500, () => {
        $modalSrcEl.children().remove();
        $modal.children('#modal-section').children('textarea').val('');
    });
    $(document).off('keydown');
}

async function submitModalData() {
    const user = await _getUser();
    // console.log(user);
    const data = {
        path: $modalSrcEl.children('div').attr('data-filename') || null,
        text: $modal.children('#modal-section').children('textarea').val().trim() || null,
        author: user === ';' ? null : user
    };

    if (!data.text || !data.author) {
        if (!data.text) _createToast('Campo de resposta vazio');
        else _createToast('Sessão expirada');
        return;
    }
    // console.table(data);
    // console.log(data.path !== null);
    $.ajax({
        url: 'php/generate.php',
        data: `path=${data.path ? data.path : 'data/questions'}&author=${data.author}&ans=${data.path !== null}&text=${data.text}`
    }).done(_ => {
        if (_ === 'err') {
            _createToast('Erro ao conectar com PHP');
            return;
        }
        // console.log($modalSrcEl);
        location.reload();
    });
}

async function _getUser() {
    return await $.ajax({
        url: 'php/authenticate.php',
        data: 'getUser=true'
    });
}

function _createToast(message) {
    const $toast = $('#warn-toast');
    $toast.text(message).fadeIn(750, () => {
        setTimeout(() => {
            $toast.fadeOut(750, () => {
                $toast.text('');
            });
        }, 3000);
    });
}

function responsively() {
    const $user = $('.user');
    const $menu = $('.menu');
    const width = $(document).width();
    if(width <= 1430) {
        wordBreak();
    } else {
        noWordBreak();
    }
    if(width <= 800) {
        setTel();
    } else {
        setPC();
    }
    function setTel() {
        $user.css('display', 'none');
        $menu.css('display', 'block');
    }
    function setPC() {
        $menu.css('display', 'none');
        $user.css('display', 'block');
    }

    function wordBreak() {
        $user.css('width', '18%');
    }
    function noWordBreak() {
        $user.css('width', '10%');
    }
}

function showHamburguer() {
    $('.burger').slideToggle('fast');
}

// function _createCookie(name, value) {
//     document.cookie = `${name}=${value}; path=/;`;
// }
//
// function _getCookie(name) {
//     return document.cookie.split(' ').filter(_ => _.includes(name))[0] || null;
// }
//
// function _deleteCookie(name) {
//     document.cookie = name + '=;expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
// }