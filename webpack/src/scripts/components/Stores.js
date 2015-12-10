import React from 'react';
import _ from 'underscore';
import Card from './Card.js';
import { Link } from 'react-router'


const Stores = React.createClass({
  getInitialState: function() {
    return {
      stores: [],
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
         this.setState({stores: data.stores});
      }.bind(this),
      error: function(xhr, status, err) {
        console.log(this.props.url, status, err.toString());
      }.bind(this)
    });
  },
  handleClick: function(item) {
    var hook_link = '/order/hook/' + item.id;
    $.ajax({
      type: "POST",
      url: hook_link,
      dataType: 'json',
      cache: false,
      success: function(data, status) {
        this.props.history.push({
          pathname: data.redirect_url,
          search: '',
          state: { the: 'state' }
        })
      }.bind(this),
      error: function(xhr, status, err) {
        alert('新增失敗');
      }.bind(this)
    });
  },
  render: function(){
    return (
        <div className="stores container">
          <div className="lin-grid">
            {this.state.stores.map(function(item, i) {
              var imageStyles = {"width": "80%"};
              var image = "/images/icon.svg"
              var boundClick = this.handleClick.bind(this, item);
              return (
                <div key={item.id} className="col-xs-6 col-md-4 col-lg-3 cursor-point" onClick={boundClick}>
                    <Card
                      image={image}
                      title={item.name}
                      subtitle="ComeBuyComeBuyComeBuyComeBuyComeBuyComeBuy"
                      imageStyles={imageStyles}
                    />
                </div>
              );
            }, this)}


          </div>
        </div>
    );
  }
});

module.exports = Stores;
