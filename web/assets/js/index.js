
/* Bootbox confirmation avant exécution action */
$(".alert-delete").click(function(e) {
    e.preventDefault();

    let $this = $(this);
    let $message = $this.attr('data-confirm-message') || 'Voulez-vous continuer votre action ?';

    bootbox.confirm({
        message: $message,
        buttons: {
            confirm: {
                label: 'Oui',
                className: 'btn-success'
            },
            cancel: {
                label: 'Non',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
            if (result) {
                //redirection
                window.location = $this.attr('href');
            }}
    });
});


/* Ajout dynamique de fichier utilisateur */
let $collectionHolder;

// setup an "add a tag" link
let $addTagButton = $('<button type="button" class="btn btn-warning mt-3"><i class="far fa-plus-square fa-lg"></i> Ajouter un document</button>');
let $newLinkLi = $('<div class="text-right" />').append($addTagButton);

$(document).ready(function() {
    // Get the ul that holds the collection of emails
    $collectionHolder = $('ul.files');

    // add the "add a tag" anchor and li to the emails ul
    $collectionHolder.append($newLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find(':input').length);

    // Get the ul that holds the collection of emails
    $collectionHolder = $('ul.files');

    // add a delete link to all of the existing tag form li elements
    $collectionHolder.find('li').each(function() {
        addTagFormDeleteLink($(this));
    });

    $addTagButton.on('click', function(e) {
        // add a new tag form (see next code block)
        addTagForm($collectionHolder, $newLinkLi);
    });//.click();
});

function addTagForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    let prototype = $collectionHolder.data('prototype');

    // get the new index
    let index = $collectionHolder.data('index');

    let newForm = prototype;
    // You need this only if you didn't set 'label' => false in your emails field in TaskType
    // Replace '__name__label__' in the prototype's HTML to
    // instead be a number based on how many items we have
    // newForm = newForm.replace(/__name__label__/g, index);

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    newForm = newForm.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    let $newFormLi = $('<li class="d-flex"></li>').append($(newForm).css('flex-grow', 1));
    $newLinkLi.before($newFormLi);

    // add a delete link to the new form
    addTagFormDeleteLink($newFormLi);
}

function addTagFormDeleteLink($tagFormLi) {
    let $removeFormButton = $('<button type="button" class="btn btn-outline-danger btn-sm mb-3"><i class="far fa-minus-square fa-lg"></i></button>');
    $tagFormLi.append($removeFormButton);

    $removeFormButton.on('click', function(e) {
        // remove the li for the tag form
        $tagFormLi.remove();
    });
}

/* AJAX exécuter action dynamiquement
$(function () {
    $('[data-btn="test"]').on('click', function(e) {
        e.preventDefault();
        let $link = $(this);
        $.get($link.attr('href'), function (datas) {
            let text = datas.is_disable ? 'Réactiver' : 'Désactiver';
            $link.find('button').toggleClass('btn-success btn-warning').text(text);
        });
        return false;
    });
});*/

