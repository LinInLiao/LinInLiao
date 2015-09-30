import React from 'react';

const Card = React.createClass({
  propTypes: {
    title: React.PropTypes.string.isRequired,
    subtitle: React.PropTypes.string,
    image: React.PropTypes.string,
    imageWrapStyles: React.PropTypes.object,
    imageStyles: React.PropTypes.object,
    link: React.PropTypes.string,
    key: React.PropTypes.string,
  },

  render: function(){
    var imagebgcolor = this.props.imagebgcolor ? this.props.imagebgcolor : 'white';
    var cx = React.addons.classSet;
    return (
      <div className="lin-card">
        <a href={this.props.link}>
          <div className="card-image-wrap">
            <div className="card-image" style={this.props.imageWrapStyles}>
              <img style={this.props.imageStyles} src={this.props.image} />
            </div>
          </div>
          <div className="card-text">
            <div className="card-title">{this.props.title}</div>
            <div className="card-subtitle">{this.props.subtitle}</div>
          </div>
        </a>
      </div>
    );
  }
});

module.exports = Card;
