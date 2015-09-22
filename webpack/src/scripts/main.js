require('bootstrap-webpack');
require('../assets/stylesheets/style.sass');

// TODO: Require assets here.
// require('../assets/images/product.png');
import Header from './components/Header.js';
import Stores from './components/Stores.js';
import StoreDrinks from './components/StoreDrinks.js';
import Drink from './components/Drink.js';



import React from 'react';

if (document.getElementById('header') !== null) {
  React.render(<Header title={document.title}/>, document.getElementById('header'));
}


if (document.getElementById('stores') !== null) {
  React.render(<Stores />, document.getElementById('stores'));
}

if (document.getElementById('store-drinks') !== null) {
  React.render(<StoreDrinks />, document.getElementById('store-drinks'));
}

if (document.getElementById('drink') !== null) {
  React.render(<Drink />, document.getElementById('drink'));
}
