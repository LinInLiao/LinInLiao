{% extends "layout.volt" %}
{% block content %}

<div id="menu"></div>



<script type="text/jsx">
  var Drinks = React.createClass({
    getInitialState: function() {
      return {
        menus: false,
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
          this.setState({menus: data.menus});
        }.bind(this),
        error: function(xhr, status, err) {
          console.error(this.props.url, status, err.toString());
        }.bind(this)
      });
    },
    renderMenus: function(menus) {
      var menu_rows = [];
      _.each(menus,function(item){
        if (item.drinks.length > 0) {
          var drinks = [];
          _.each(item.drinks, function(item){
            drinks.push(<li key={item.drink_id}>{item.drink_name}</li>);
          });
          menu_rows.push(
            <li key={item.id}>
              <div>{item.name}</div>
              <ul>{drinks}</ul>
            </li>);
        }
      });
      return menu_rows;
    },
    render: function(){
      var menu_rows = this.renderMenus(this.state.menus);
      return (
          <ul>{menu_rows}</ul>
      );
    }
  });
  React.render(
    <Drinks />,
    document.getElementById('menu')
  );
</script>
{% endblock %}
