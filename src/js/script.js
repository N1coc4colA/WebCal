var __js_load_counter = 0;

function post_load()
{
    const bodies = document.getElementsByTagName("body");
    for (let k = 0; k < bodies.length; ++k) {
        if (bodies[k].hasAttribute("page-name")) {
            const pn = bodies[k].getAttribute("page-name");
            let navs = document.getElementsByClassName("navbar");
            for (let i = 0; i < navs.length; ++i) {
                let entries = navs[i].getElementsByClassName("nav-link");
                for (let j = 0; j < entries.length; ++j) {
                    if (entries[j].hasAttribute("page-name") && entries[j].getAttribute("page-name") == pn) {
                        entries[j].classList.add("active");
                    } else {
                        entries[j].classList.remove("active");
                    }
                }
            }
        }
    }
}

function resolve_include(element)
{
    __js_load_counter++;

    // Load the content of the file referred to by the href attr.
    fetch(element.getAttribute("href"))
    .then(response => response.text())
    .then(data => {
        // Then just replace content.
        element.innerHTML = data;
        __js_load_counter--;
        if (__js_load_counter == 0) {
            post_load();
        }
    })
    .catch(error => {
        console.error('Inclusion loading failed:', error);
        __js_load_counter--;
        if (__js_load_counter == 0) {
            post_load();
        }
    });
}

function resolve_dependency(element)
{
    __js_load_counter++;

    const scriptElem = document.createElement("script");

    for (let attr of element.attributes) {
        if (attr.name !== "order") {
            scriptElem.setAttribute(attr.name, attr.value);
        }
    }

    scriptElem.textContent = element.textContent;
    scriptElem.async = false;

    // Replace old element with the new script element
    element.parentNode.replaceChild(scriptElem, element);
}

function setup()
{
    let tags = document.getElementsByTagName("include");
    for (let i = 0; i < tags.length; ++i) {
        if (tags[i].hasAttribute("href")) {
            resolve_include(tags[i]);
        }
    }

    let deps = document.getElementsByTagName("dependency");

    let ordering = [];
    for (const dep of deps) {
        if (dep.hasAttribute("order")) {
            const order = parseInt(dep.getAttribute("order"));
            ordering.push({element: dep, order: order});
        } else {
            ordering.push({element: dep, order: Number.MAX_SAFE_INTEGER});
        }
    }
    ordering.sort((a, b) => a.order - b.order);

    for (const dep of ordering) {
        resolve_dependency(dep.element);
    }

    post_load();
}

document.addEventListener('DOMContentLoaded', setup);
