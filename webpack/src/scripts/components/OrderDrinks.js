import React from 'react';
import _ from 'underscore';
var DragOrderDrink = require('./DragOrderDrink.js');
var Hammer = require('react-hammerjs');

const OrderDrinks = React.createClass({
  getInitialState: function() {
    var location_href = window.location.href.split("/");
    location_href.pop();
    var order_id = _.last(location_href)

    return {
      orderDrinks: [],
      order_id: order_id,
      active_item: '',
    };
  },

  componentDidMount: function() {
    this.loadOrderDrinks();
  },
  loadOrderDrinks: function() {
    console.log();
    $.ajax({
      url: '/resource/oDrinkList/' + this.state.order_id,
      dataType: 'json',
      cache: false,
      success: function(data, status) {
        this.setState({orderDrinks: data.drinks});
      }.bind(this),
      error: function(xhr, status, err) {
        // console.log(this.props.url, status, err.toString());
      }.bind(this)
    });
  },
  handleOnTap: function(item_name) {
    if (this.state.active_item !== item_name) {
      this.setState({
        active_item: item_name,
      });
    }
  },
  handleOnPan: function(item_name) {
    if (this.state.active_item !== item_name) {
      this.setState({
        active_item: item_name,
      });
    }
  },
  render: function(){
    var rows = [];
    this.state.orderDrinks.forEach(function(drink) {
      rows.push(
        <Hammer key={drink.id} onTap={this.handleOnTap.bind(this, drink.id)} onPan={this.handleOnPan.bind(this, drink.id)}>
          <DragOrderDrink active={this.state.active_item} itemKey={drink.id} drink={drink}>
          </DragOrderDrink>
        </Hammer>
      );
    }, this);
    return (
      <div className="order-drinks">
        {rows}
      </div>
    );
  }
});

module.exports = OrderDrinks;
