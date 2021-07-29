/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';
import './styles/commerce/package.scss';
import './styles/commerce/store.scss';


// javascript
const $ = require("jquery");
require('@fortawesome/fontawesome-free/js/all.js');

require("bootstrap/js/dist/dropdown")
require("./js/_main.js");


if (Number.isInteger($('#commerce_checkout_form_commerceGatewayInstance').value))
{
    $('form[name="commerce_checkout_form"]').submit();
}

$('[data-toggle="dropdown"]').dropdown();
$('#commerce_checkout_form_commerceGatewayInstance').change((a) => {
    if (a.currentTarget.value != "")
    {
        $('form[name="commerce_checkout_form"]').submit();
    }
});

// start the Stimulus application
import './bootstrap';

