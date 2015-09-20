import React from 'react';
import _ from 'underscore';
import $ from 'jQuery';


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
      store_rows.push(
        <a key={item.id} href={hook_link} className="list-group-item">
          <span>{item.name}</span>(點擊產生訂單)
        </a>);
    });
    return store_rows;
  },
  render: function(){
    return (
        <div className="list-group">{this.state.stores}</div>
    );
  }
});

module.exports = Stores;
