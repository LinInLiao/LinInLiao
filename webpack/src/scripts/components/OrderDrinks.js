import React from 'react';
import _ from 'underscore';
var DragOrderDrink = require('./DragOrderDrink.js');
var Hammer = require('react-hammerjs');

const OrderDrinks = React.createClass({
  getInitialState: function() {
    return {
      store_drinks: [],
      order_id: _.last(window.location.href.split("/")),
      active_item: '',
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
    return (
      <div className="order-drinks">
        <Hammer onTap={this.handleOnTap.bind(this,'item-1')} onPan={this.handleOnPan.bind(this,'item-1')}><DragOrderDrink active={this.state.active_item} itemKey="item-1" /></Hammer>
        <Hammer onTap={this.handleOnTap.bind(this,'item-2')} onPan={this.handleOnPan.bind(this,'item-2')}><DragOrderDrink active={this.state.active_item} itemKey="item-2" /></Hammer>
        <Hammer onTap={this.handleOnTap.bind(this,'item-3')} onPan={this.handleOnPan.bind(this,'item-3')}><DragOrderDrink active={this.state.active_item} itemKey="item-3" /></Hammer>
        <Hammer onTap={this.handleOnTap.bind(this,'item-4')} onPan={this.handleOnPan.bind(this,'item-4')}><DragOrderDrink active={this.state.active_item} itemKey="item-4" /></Hammer>
        <Hammer onTap={this.handleOnTap.bind(this,'item-5')} onPan={this.handleOnTap.bind(this,'item-5')}><DragOrderDrink active={this.state.active_item} itemKey="item-5" /></Hammer>
        <Hammer onTap={this.handleOnTap.bind(this,'item-6')} onPan={this.handleOnTap.bind(this,'item-6')}><DragOrderDrink active={this.state.active_item} itemKey="item-6" /></Hammer>
        <Hammer onTap={this.handleOnTap.bind(this,'item-7')} onPan={this.handleOnTap.bind(this,'item-7')}><DragOrderDrink active={this.state.active_item} itemKey="item-7" /></Hammer>
        <Hammer onTap={this.handleOnTap.bind(this,'item-8')} onPan={this.handleOnTap.bind(this,'item-8')}><DragOrderDrink active={this.state.active_item} itemKey="item-8" /></Hammer>
        <Hammer onTap={this.handleOnTap.bind(this,'item-9')} onPan={this.handleOnTap.bind(this,'item-9')}><DragOrderDrink active={this.state.active_item} itemKey="item-9" /></Hammer>
      </div>
    );
  }
});

module.exports = OrderDrinks;
