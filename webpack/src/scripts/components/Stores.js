import React from 'react';
import _ from 'underscore';


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
        <a key={item.id} href={hook_link}>
          <div className="lin-card">
            <div className="card-image-wrap">
              <div className="card-image"><img src="http://fakeimg.pl/200/"/></div>
            </div>
            <div className="card-text">
              <div className="card-title">{item.name}</div>
              <div className="card-description">$20</div>
            </div>
          </div>
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
