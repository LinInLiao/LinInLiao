import React from 'react';
import _ from 'underscore';
var DragOrderDrink = require('./DragOrderDrink.js');

const OrderDrinks = React.createClass({
  getInitialState: function() {
    return {
      store_drinks: [],
      order_id: _.last(window.location.href.split("/"))
    };
  },

  componentDidMount: function() {
    this.loadOrderDrinks();
  },
  loadOrderDrinks: function() {
    $.ajax({
      url: '/resource/oDrinkList/' + this.state.order_id,
      dataType: 'json',
      cache: false,
      success: function(data, status) {
        this.setState({store_drinks: data.drinks});
      }.bind(this),
      error: function(xhr, status, err) {
        console.log(this.props.url, status, err.toString());
      }.bind(this)
    });
  },

  render: function(){
    return (
      <div className="order-drinks">
        <DragOrderDrink/>
        <DragOrderDrink/>
        <DragOrderDrink/>
        <DragOrderDrink/>
        <DragOrderDrink/>
        <DragOrderDrink/>
        <DragOrderDrink/>
        <DragOrderDrink/>
        <DragOrderDrink/>
        <DragOrderDrink/>
        <DragOrderDrink/>
        <DragOrderDrink/>
        <DragOrderDrink/>
        <DragOrderDrink/>
      </div>
    );
  }
});

module.exports = OrderDrinks;
