'use strict';

let urlParams = new URLSearchParams(window.location.search);
console.log(urlParams);
if (urlParams.has('highlight')) {
    let e = document.querySelector('.trip[data-id="'+ urlParams.get('highlight') +'"]');
    e.classList.add('highlighted');
}

const removalConfirm = (id) => {
    if (window.confirm('Do you really want to remove that trip?')) {
        window.location.href = '/remove/' + id;
    }
}