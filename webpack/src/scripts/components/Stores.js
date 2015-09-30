import React from 'react';
import _ from 'underscore';
import Card from './Card.js';


const Stores = React.createClass({
  getInitialState: function() {
    return {
      stores: false,
    };
  },

  componentDidMount: function() {
    this.loadStores();
  },
  loadStores: function() {
    $.ajax({
      url: '/resource/stores',
      dataType: 'json',
      cache: false,
      success: function(data, status) {
         var store_list = this.renderStores(data.stores);
         this.setState({stores: store_list});
      }.bind(this),
      error: function(xhr, status, err) {
        console.log(this.props.url, status, err.toString());
      }.bind(this)
    });
  },
  renderStores: function(stores) {
    var store_rows = [];
    stores.map(function(item){
      var hook_link = 'order/hook/' + item.id;
      var imageStyles = {"width": "80%"};
      var image = "/images/icon.svg"

      store_rows.push(
        <div key={item.id} className="col-xs-6 col-md-4 col-lg-3">
          <Card
            link={hook_link}
            image={image}
            title={item.name}
            subtitle="ComeBuyComeBuyComeBuyComeBuyComeBuyComeBuy"
            imageStyles={imageStyles}
          >
          </Card>
        </div>
        );
    });
    return store_rows;
  },
  render: function(){
    return (
        <div className="stores container">
          <div className="lin-grid">{this.state.stores}</div>
        </div>
    );
  }
});

module.exports = Stores;
