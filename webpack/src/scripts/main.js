require('bootstrap-webpack');
require('../assets/stylesheets/style.sass');

// TODO: Require assets here.
// require('../assets/images/product.png');

import Stores from './components/Stores.js';
import StoreDrinks from './components/StoreDrinks.js';


import React from 'react';

if (document.getElementById('stores') !== null) {
  React.render(<Stores />, document.getElementById('stores'));
}

if (document.getElementById('store-drinks') !== null) {
  React.render(<StoreDrinks />, document.getElementById('store-drinks'));
}
