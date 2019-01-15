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
    elms.odometerEndLabel = form.querySelector('[for=OdometerEnd]')
    elms.checkKm = form.querySelector('[data-field="km"]')
    elms.checkHours = form.querySelector('[data-field="hours"]')
    elms.timeEnd = form.querySelector('#TimeEnd');
    elms.timeStart = form.querySelector('#TimeStart');
    elms.onlyForWork = form.querySelector('.onlyForWork')


    let affected = [elms.onlyForWork]
    if (formData.Personal == 1) {
        affected.forEach((one) => {
            one.classList.add('hide')
        })
    } else {
        affected.forEach((one) => {
            one.classList.remove('hide')
        })
    }

    affected = [elms.placeEnd, elms.placeEndLabel]
    if (formData.AndBack == 1) {
        affected.forEach((one) => {
            one.classList.add('hide')
        })
    } else {
        affected.forEach((one) => {
            one.classList.remove('hide')
        })
    }

    elms.checkKm.classList.remove('invalid')
    if (formData.OdometerEnd != '' && formData.OdometerStart != '') {
        elms.checkKm.innerHTML = formData.OdometerEnd - formData.OdometerStart
        if ((formData.OdometerEnd - formData.OdometerStart) <= 0) {
            elms.checkKm.classList.add('invalid')
        }
    } else {
        elms.checkKm.innerHTML = '--'

    }

    if (formData.TimeStart == '') {
        let now = new Date()
        let justMinutes = [
            now.getFullYear(),
            '-',
            ('00' + (now.getMonth() +1)).substr(-2, 2),
            '-',
            ('00' + now.getDate()).substr(-2, 2),
            'T',
            ('00' + now.getHours()).substr(-2, 2),
            ':',
            ('00' + now.getMinutes()).substr(-2, 2)
        ].join('')
        elms.timeStart.value = justMinutes

    }
    formData = extractFormData(form)

    elms.checkHours.classList.remove('invalid')
    if (formData.TimeEnd != '' && formData.TimeStart != '') {
        let s = new Date(formData.TimeStart)
        let e = new Date(formData.TimeEnd)
        let diff = (e - s) / (1000 * 60 * 60)
        elms.checkHours.innerHTML = diff.toFixed(1);
        if (diff <= 0) {
            elms.checkHours.classList.add('invalid')
        }
    } else {
        elms.checkHours.innerHTML = '--'

    }

    /* sets btnGroup class to what button is on - for styling purposes */
    let btnGroups = form.querySelectorAll('.btnGroup');
    btnGroups.forEach((group) => {

        group.classList.remove('on0', 'on1')

        let btns = group.querySelectorAll('button');
        let onIndex = null;
        btns.forEach((one, idx) => {
            if (one.classList.contains('on')) {
                onIndex = idx;
            }
        })
        group.classList.add('on' + onIndex);
    })

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

            let allInGroup = document.querySelectorAll('[data-form-element='+ targetElementId +']')
            allInGroup.forEach((one) => {
                one.classList.remove('on')
            })
            event.target.classList.add('on')
            targetElement.dispatchEvent(change);
        })
    })
})