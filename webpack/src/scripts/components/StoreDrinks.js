import React from 'react';
import _ from 'underscore';
import Card from './Card.js';
import Tabs from 'react-simpletabs';



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
      var hook_link = 'order/hook/' + item.id;
      var imageStyles = {"width": "80%"};
      var image = "/images/icon.svg"
      return (
        <div key={item.drink_id} className="col-xs-6 col-md-4 col-lg-3">
          <Card
            link={this.state.order_id + '/' + item.drink_id + '/' + item.drink_coldheat}
            image={image}
            title={item.drink_name}
            subtitle="20元"
            imageStyles={imageStyles}
          >
          </Card>
        </div>
      );
    }.bind(this));
    return (drinks_list);
  },
  renderCategories: function(categories) {
    var categories_list = categories.map(function(item){
      if (item.drinks.length > 0) {
        var drinks = this.renderDrinks(item.drinks);
      }else {
         return;
      }
      return (
        <div key={item.id} className="category-group clearfix">
          <div className="category-title">{item.name}</div>
          <div className="category-drinks lin-grid">{drinks}</div>
        </div>
      );
    }.bind(this));
    return (categories_list);
  },
  renderTabs: function(tabs) {
    var tabSelectedIndex = 1;
    var tabs_list = tabs.map(function(item){
      return (
        <Tabs.Panel key={item.coldheatid} title={item.name}>
          {this.renderCategories(item.categories)}
        </Tabs.Panel>
      );
    }.bind(this));
    return (<Tabs className="lin-tabs">{tabs_list}</Tabs>);
  },
  render: function(){
    var store_menu = '';
    if (this.state.store_drinks !== false) {
      store_menu = this.renderTabs(this.state.store_drinks);
    }

    return (
        <div className="drinks container">
          {store_menu}
        </div>
    );
  }
});

module.exports = StoreDrinks;
