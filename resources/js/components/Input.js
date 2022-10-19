import PropTypes from "prop-types";
import React, { useState, useEffect } from "react";

const Input = ({
  data,
  index,
  clear,
  setData,
  options,
  setError,
  lastIndex,
}) => {
  const [inputValue, setInputValue] = useState("");
  const [selectValue, setSelectValue] = useState("");

  useEffect(() => {
    setInputValue("");
    setSelectValue("");
  }, [clear]);

  const handleSelect = (e) => {
    const { value } = e.target;

    setError(false);
    setSelectValue(value);
    if (data.length === index) {
      data.push({ component_ref_id: value, value: "" });
    } else {
      data[index].component_ref_id = value;
    }
    setData(data);
  };

  const handleInput = (e) => {
    const { value } = e.target;
    const re = /^[0-9\b]+$/;

    if (value === "" || re.test(value)) {
      data[index].value = value;
      setData(data);
      setError(false);
      setInputValue(value);
    }
  };

  return (
    <div className="d-flex align-items-center my-3">
      <div className="d-flex align-items-center col-md-6">
        <label className="col-md-3" htmlFor="component">
          Component:
        </label>
        <select
          className="form-control"
          id="component"
          value={selectValue}
          onChange={handleSelect}
          disabled={!lastIndex}
        >
          <option>Choose</option>
          {options.map((option, key) => {
            return <option value={option.value}>{option.label}</option>;
          })}
        </select>
      </div>
      <div className="d-flex align-items-center col-md-6">
        <label className="col-md-2 mx-3" htmlFor="add">
          Value:
        </label>
        <input
          id="add"
          name="add"
          className="form-control"
          placeholder="Initial estimate..."
          value={inputValue}
          onChange={handleInput}
          disabled={!lastIndex}
        />
      </div>
    </div>
  );
};

Input.propTypes = {
  clear: PropTypes.bool.isRequired,
  data: PropTypes.shape({
    component_ref_id: PropTypes.string,
    value: PropTypes.number,
  }),
  index: PropTypes.number.isRequired,
  lastIndex: PropTypes.number.isRequired,
  options: PropTypes.arrayOf({
    value: PropTypes.string,
    label: PropTypes.string,
  }),
  setData: PropTypes.func.isRequired,
  setError: PropTypes.func.isRequired,
};

export default Input;
