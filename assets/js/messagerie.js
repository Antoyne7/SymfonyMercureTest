/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import '../css/messagerie.sass';

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
import $ from 'jquery';
//
// window.jQuery = $;
// let jQuery = $;

// URL is a built-in JavaScript class to manipulate URLs
const url = new URL('http://localhost:3000/.well-known/mercure');
// url.searchParams.append('topic', 'http://monsite.com/user/{id}');
url.searchParams.append('topic', 'ping/{id}');

const eventSource = new EventSource(url, {withCredentials: true});
eventSource.onmessage = event => {
    const data = JSON.parse(event.data);
    const sender = JSON.parse(data[0].sendBy);
    let user = "anon998";
    if(sender.email){
        user = sender.email.split("@")[0];
    }
    $('#messages').append(`<li><div>${user}</div><div>${data[2]}</div></li>`);
};

console.log('Hello Webpack Encore! Edit me in assets/js/messagerie.js');


