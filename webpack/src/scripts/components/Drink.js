import React from 'react';
import _ from 'underscore';


const Drink = React.createClass({
  getInitialState: function() {
    var path = window.location.href.split("/").reverse();
    var coldheat_id = path[0];
    var drink_id = path[1];
    var order_id = path[2];

    return {
      order_id: order_id,
      drink_id: drink_id,
      coldheat_id: coldheat_id,
      soruce_path: '/resource/oDrink/' + drink_id + '/' + coldheat_id,
      drink: {
        name: ''
      },
      sizes: '',
      sugars: '',
      coldheat_levels: '',
      extras: '',
    };
  },

  componentDidMount: function() {
    this.loadDrink();
  },

  loadDrink: function() {
    $.ajax({
      url: this.state.soruce_path,
      dataType: 'json',
      cache: false,
      success: function(data, status) {
        if (data.drink != undefined) {
          var drink = {
            name: data.drink.name
          };
          var sugars = this.renderSugars(data.drink.sugars);
          var coldheat_levels = this.renderColdHeatLevels(data.drink.coldheat_levels);
          var sizes = this.renderSizes(data.drink.sizes);
          if (data.drink.extras != false) {
            var extras = this.renderExtras(data.drink.extras);
          }
        }
        this.setState({
          drink: drink,
          sugars: sugars,
          coldheat_levels: coldheat_levels,
          sizes: sizes,
          extras: extras ? extras : '',

        });
      }.bind(this),
      error: function(xhr, status, err) {
        console.error(this.props.url, status, err.toString());
      }.bind(this)
    });
  },
  renderSugars: function(sugars){
    var sugars_rows  = sugars.map(function(item){
      return (<div key={item.store_sugar_id}>{item.name}</div>);
    });
    return (
        <div>{sugars_rows}</div>
    );
  },
  renderColdHeatLevels: function(coldheat_levels){
    var coldheat_levels_rows  = coldheat_levels.map(function(item){
      return (<div key={item.store_coldheat_level_id}>{item.name}</div>);
    });
    return (
        <div>{coldheat_levels_rows}</div>
    );
  },
  renderSugars: function(sugars){
    var sugars_rows  = sugars.map(function(item){
      return (<div key={item.store_sugar_id}>{item.name}</div>);
    });
    return (
        <div>{sugars_rows}</div>
    );
  },
  renderSizes: function(sizes){
    var sizes_rows  = sizes.map(function(item){
      return (<div key={item.store_size_id}>{item.name} : {item.price}</div>);
    });
    return (
        <div>{sizes_rows}</div>
    );
  },
  renderExtras: function(extras){
    var extras_rows  = extras.map(function(item){
      return (<div key={item.store_size_id}>{item.name} : {item.price}</div>);
    });
    return (
        <div>{extras_rows}</div>
    );
  },
  render: function(){
    return (
      <div>
        <div>{this.state.drink.name}</div>
        <div>{this.state.sugars}</div>
        <div>{this.state.coldheat_levels}</div>
        <div>{this.state.sizes}</div>
        <div>{this.state.extras}</div>
        <button>送出訂單</button>
      </div>
    );
  }
});

module.exports = Drink;
