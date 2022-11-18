export const renderFields = items => {
  return items.map((field, i) => <field.component {...field.props} key={i} />);
};
