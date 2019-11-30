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
import 'prismjs/components/prism-css';
import 'prismjs/components/prism-css-extras';
import 'prismjs/components/prism-scss';
import 'prismjs/components/prism-bash';
import 'prismjs/components/prism-markup-templating';
import 'prismjs/components/prism-clike';
import 'prismjs/components/prism-php';
import 'prismjs/components/prism-php-extras';
import 'prismjs/components/prism-java';
import 'prismjs/components/prism-javadoclike';
import 'prismjs/components/prism-javadoc';
import 'prismjs/components/prism-phpdoc';
import 'prismjs/components/prism-javascript';
import 'prismjs/components/prism-yaml';
import 'prismjs/components/prism-json';
import 'prismjs/components/prism-jsonp';
import 'prismjs/components/prism-json5';
import 'prismjs/components/prism-docker';
import 'prismjs/components/prism-http';
import 'prismjs/components/prism-twig';
import 'prismjs/components/prism-sql';
import 'prismjs/components/prism-graphql';
import 'prismjs/components/prism-diff';
// Note: this is huge because their autoloader does not work with webpack...

// Import Prism JS
import 'prismjs/plugins/line-numbers/prism-line-numbers';
import 'prismjs/plugins/line-numbers/prism-line-numbers.css';

// Highlight all matching syntax
Prism.highlightAll();
