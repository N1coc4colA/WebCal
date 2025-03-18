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

document.addEventListener('DOMContentLoaded', () => {
    let tags = document.getElementsByTagName("include");

    if (tags.length == 0) { // No need to run the later.
        return post_load();
    }

    for (let i = 0; i < tags.length; ++i) {
        if (tags[i].hasAttribute("href")) {
            resolve_include(tags[i]);
        }
    }
});
