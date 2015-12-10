

require("bootstrap/less/bootstrap.less")
require('../assets/stylesheets/style.sass');
import React from 'react';
import ReactDOM from 'react-dom';
import { createHistory } from 'history';
let history = createHistory();

import { Router, Route, Link, IndexRoute } from 'react-router'

import App from './components/app.js';
import Header from './components/Header.js';
import Stores from './components/Stores.js';
import StoreDrinks from './components/StoreDrinks.js';
import Drink from './components/Drink.js';
import OrderDrinks from './components/OrderDrinks.js';

history.push({
  pathname: window.location.pathname ? window.location.pathname : '/',
  search: '',
  state: { the: 'state' }
})


ReactDOM.render(
    <Router history={history}>
    <Route path="/" component={App}>
      <IndexRoute component={Stores} />
      <Route path="order/:orderID" component={StoreDrinks}/>
      <Route path="order/:orderID/:drinkID/:coldheatID" component={Drink}/>
      <Route path="order/:orderID/overview" component={OrderDrinks}/>
    </Route>
  </Router>
  , document.getElementById('App'));


if (document.getElementById('header') !== null) {
  ReactDOM.render(<Header title={document.title}/>, document.getElementById('header'));
}


// if (document.getElementById('stores') !== null) {
//   ReactDOM.render(<Stores />, document.getElementById('App'));
// }

// if (document.getElementById('store-drinks') !== null) {
//   ReactDOM.render(<StoreDrinks />, document.getElementById('store-drinks'));
// }

// if (document.getElementById('drink') !== null) {
//   ReactDOM.render(<Drink />, document.getElementById('drink'));
// }

// if (document.getElementById('orderDrinks') !== null) {
//   ReactDOM.render(<OrderDrinks />, document.getElementById('orderDrinks'));
// }
