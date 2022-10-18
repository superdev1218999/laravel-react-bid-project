import React, { useState, useEffect } from "react";

const Input = ({ index, lastIndex, data, setData, setError, clear }) => {
  const [inputValue, setInputValue] = useState("");
  const [selectValue, setSelectValue] = useState("");

  useEffect(() => {
    setInputValue("");
    setSelectValue("");
  }, [clear]);

  const handleSelect = (e) => {
    const { value } = e.target;

    data.push({ id: value, value: "" });
    setData(data);
    setError(false);
    setSelectValue(value);
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
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
          <option value="4">4</option>
          <option value="5">5</option>
          <option value="6">6</option>
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

export default Input;
