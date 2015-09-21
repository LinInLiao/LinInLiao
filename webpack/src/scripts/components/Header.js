import React from 'react';
import _ from 'underscore';


const Header = React.createClass({
  getInitialState: function() {
    return {
      title: this.props.title,
    };
  },


  render: function(){
    return (
        <div className="header-wrap">
          <h1>{this.props.title}</h1>
        </div>
    );
  }
});

module.exports = Header;
