function addForm(){
    const list = window.document.getElementById('respuestas-list');
    let prototype = list.getAttribute('data-prototype');
    let counter = list.children.length;

    prototype = prototype.replace(/__name__/g, counter);
    counter++

    list.setAttribute('widget-counter', counter);

    const listItem = window.document.createElement('li')
    listItem.innerHTML = prototype
    list.appendChild(listItem)
}