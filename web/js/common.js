function oai_toggle(id, visible_style)
{
  if (undefined == visible_style)
  {
    visible_style = 'block';
  }

  var element = document.getElementById(id);

  if (undefined != element)
  {
    element.style.display = element.style.display == 'none' ? visible_style : 'none';
  }

  return element;
}
