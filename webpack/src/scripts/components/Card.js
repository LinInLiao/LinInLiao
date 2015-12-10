import React from 'react';

const Card = React.createClass({
  propTypes: {
    title: React.PropTypes.string.isRequired,
    subtitle: React.PropTypes.string,
    image: React.PropTypes.string,
    imageWrapStyles: React.PropTypes.object,
    imageStyles: React.PropTypes.object,
    key: React.PropTypes.string,
  },

  render: function(){
    var imagebgcolor = this.props.imagebgcolor ? this.props.imagebgcolor : 'white';
    return (
      <div className="lin-card">
          <div className="card-image-wrap">
            <div className="card-image" style={this.props.imageWrapStyles}>
              <img style={this.props.imageStyles} src={this.props.image} />
            </div>
          </div>
          <div className="card-text">
            <div className="card-title">{this.props.title}</div>
            <div className="card-subtitle">{this.props.subtitle}</div>
          </div>
      </div>
    );
  }
});

module.exports = Card;
