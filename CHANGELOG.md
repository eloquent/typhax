# Typhax changelog

## 0.10.1 (2015-11-13)

- **[FIXED]** Fixed specification with regards to array types where no
  traversable details are supplied.

## 0.10.0 (2015-11-12)

- **[BC BREAK]** Major overhaul of all classes.
- **[BC BREAK]** Updated the specification with regards to how omitting the key
  type of a traversable is interpreted. Omitted keys now indicate a traversable
  with sequential integer keys ([#46]).

[#46]: https://github.com/eloquent/typhax/issues/46

## 0.9.1 (2013-03-04)

- **[NEW]** [Archer] integration.
- **[NEW]** Implemented changelog.

[archer]: https://github.com/IcecaveStudios/archer
