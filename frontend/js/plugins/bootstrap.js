// Hỗ trợ nhiều modal nhiều lớp
// https://stackoverflow.com/questions/19305821/multiple-modals-overlay
$(document).on('show.bs.modal', '.modal', function () {
    const zIndex = 1040 + (10 * $('.modal:visible').length);
    $(this).css('z-index', zIndex);
    setTimeout(function () {
        $('.modal-backdrop')
            .not('.modal-stack')
            .css('z-index', zIndex - 1)
            .addClass('modal-stack');
    }, 0);
});

$(document).on('hidden.bs.modal', function () {
    // Nếu vẫn còn modal nữa nên phải làm thế này thì modal mới scroll được
    if ($('.modal:visible').length) {
        document.body.classList.add('modal-open');
    }
});
