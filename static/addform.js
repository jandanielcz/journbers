'use strict'

const extractFormData = (form) => {
    let children = form.getElementsByTagName('*')

    children = Array.from(children).filter((one) => {
        return (one.tagName == 'TEXTAREA' || one.tagName == 'INPUT')
    })

    let result = children.reduce((acc, one) => {
        if (one.getAttribute('name') === null) {
            return acc
        }
        acc[one.getAttribute('name')] = one.value
        return acc
    }, {});

    return result
}

const applyFormData = () => {
    const form = document.querySelector('#AddForm form')
    let formData = extractFormData(form)

    /**/
    let elms = {};
    elms.client = form.querySelector('#Client')
    elms.placeTarget = form.querySelector('#PlaceTarget')
    elms.clientLabel = form.querySelector('[for=Client]')
    elms.placeTargetLabel = form.querySelector('[for=PlaceTarget]')
    elms.andBackButton = form.querySelector('[data-form-element="AndBack"][value="1"]')
    elms.placeEnd = form.querySelector('#PlaceEnd')
    elms.placeEndLabel = form.querySelector('[for=PlaceEnd]')
    elms.odometerEnd = form.querySelector('#OdometerEnd')
    elms.orometerEndLabel = form.querySelector('[for=OdometerEnd]')

    elms.andBackButton.textContent = 'Back to ' + formData.PlaceStart

    let affected = [elms.client, elms.clientLabel, elms.placeTarget, elms.placeTargetLabel]
    if (formData.Personal == 1) {
        affected.forEach((one) => {
            one.classList.add('hide')
        })
    } else {
        affected.forEach((one) => {
            one.classList.remove('hide')
        })
    }

    affected = [elms.placeEnd, elms.placeEndLabel, elms.odometerEnd, elms.orometerEndLabel]
    if (formData.AndBack == 1) {
        affected.forEach((one) => {
            one.classList.add('hide')
        })
    } else {
        affected.forEach((one) => {
            one.classList.remove('hide')
        })
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('#AddForm form')

    applyFormData()

    form.addEventListener('change', (event) => {
        applyFormData()
    })
    form.addEventListener('keyup', (event) => {
        applyFormData()
    })
    const switches = document.querySelectorAll('[data-form-element]')
    switches.forEach((one) => {
        one.addEventListener('click', (event) => {
            event.preventDefault();
            let val = event.target.getAttribute('value')
            let targetElementId = event.target.getAttribute('data-form-element')
            let targetElement = document.querySelector('[name=' + targetElementId + ']');
            targetElement.setAttribute('value', val);
            let change = new Event('change', {bubbles: true});
            targetElement.dispatchEvent(change);
            let allInGroup = document.querySelectorAll('[data-form-element='+ targetElementId +']')
            allInGroup.forEach((one) => {
                one.classList.remove('on')
            })
            event.target.classList.add('on')

        })
    })
})