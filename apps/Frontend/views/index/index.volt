
{% if Stores is defined AND Stores is not false %}
  <ul>
  {% for store in Stores %}

    <li><span>{{ store.name }}</span> <a href="/order/hook/{{ store.id }}">Hook Order</a></li>
  {% endfor %}
  </ul>
{% endif %}
