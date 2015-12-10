import React from 'react';


const App = React.createClass({
  render() {
    return (
    <div className="l-main">
        {this.props.children}
    </div>
    );
  }
});

module.exports = App;
