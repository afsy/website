/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/reset.css');
require('../css/global.css');
require('../css/layout.css');
require('../css/app.css');
require('../css/grid.css');
require('../css/icons.css');
require('../css/avent.css');

const $ = require('jquery');

// Import PrismJS package
import Prism from 'prismjs';

// Import PrismJS extensions
import 'prismjs/themes/prism-twilight.css';
import 'prismjs/components/prism-scss';

// Import Prism JS
import 'prismjs/plugins/line-numbers/prism-line-numbers';
import 'prismjs/plugins/line-numbers/prism-line-numbers.css';

// Highlight all matching syntax
Prism.highlightAll();
