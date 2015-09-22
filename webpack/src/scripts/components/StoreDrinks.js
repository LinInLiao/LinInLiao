import React from 'react';
import _ from 'underscore';


const StoreDrinks = React.createClass({
  getInitialState: function() {
    return {
      store_drinks: false,
      order_id: _.last(window.location.href.split("/"))
    };
  },

  componentDidMount: function() {
    this.loadStoreDrinks();
  },
  loadStoreDrinks: function() {
    $.ajax({
      url: '/resource/oStore/' + this.state.order_id,
      dataType: 'json',
      cache: false,
      success: function(data, status) {
        this.setState({store_drinks: data.drinks});
      }.bind(this),
      error: function(xhr, status, err) {
        console.error(this.props.url, status, err.toString());
      }.bind(this)
    });
  },
  renderDrinks: function(drinks) {
    var drinks_list = drinks.map(function(item){
      return (
          <a href={this.state.order_id + '/' + item.drink_id + '/' + item.drink_coldheat} className="list-group-item">{item.drink_name}</a>
      );
    }.bind(this));
    return (<div className="list-group">{drinks_list}</div>);
  },
  renderCategories: function(categories) {
    var categories_list = categories.map(function(item){
      if (item.drinks.length > 0) {
        var drinks = this.renderDrinks(item.drinks);
      }else {
         return;
      }
      return (
        <div className="panel panel-primary">
          <div className="panel-heading">{item.name}</div>
          <div className="panel-body">{drinks}</div>
        </div>
      );
    }.bind(this));
    return (categories_list);
  },
  render: function(){
    var store_list = false;
    if (this.state.store_drinks !== false) {
      store_list = this.state.store_drinks.map(function(item){
        if (item.categories.length > 0) {
          var categories = this.renderCategories(item.categories);
        }else {
          return;
        }
        return (
          <div className="panel panel-primary">
            <div className="panel-heading">{item.name}</div>
            <div className="panel-body">{categories}</div>
          </div>
        );
      }.bind(this));
    }
    return (
        <div>{store_list}</div>
    );
  }
});

module.exports = StoreDrinks;
