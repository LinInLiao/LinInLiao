
{% if Stores is defined AND Stores is not false %}
  <ul>
  {% for store in Stores %}
    <li><a href="testMenu/{{ store.id }}">{{ store.name }}</a></li>

  {{ dump(store) }}
  {% endfor %}
  </ul>
{% endif %}
