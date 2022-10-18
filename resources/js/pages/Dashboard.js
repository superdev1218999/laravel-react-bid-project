import _ from "lodash";
import React, { useState } from "react";
import { Table } from "reactstrap";

import Http from "../Http";
import Input from "../components/Input";

const Dashboard = () => {
  const [clear, setClear] = useState(false);
  const [index, setIndex] = useState(1);
  const [data, setData] = useState([]);
  const [error, setError] = useState(false);

  const addElement = () => {
    if (!data[index - 1]?.value) {
      setError(true);
    } else {
      setIndex((prevIndex) => prevIndex + 1);
    }
  };

  const clearElement = () => {
    setData([]);
    setIndex(1);
    setClear((prev) => !prev);
  };

  const handleSubmit = () => {
    if (!data[index - 1]?.value) {
      setError(true);
    } else {
      console.log(data);
    }
  };

  return (
    <div className="container py-5">
      <div className="mb-5">
        <h1 className="text-center mb-4">Add a Initial "Bid"</h1>
        <div className="d-flex align-items-center justify-content-between">
          <h5>Create Initial "Bid" to component or line item you want.</h5>
          <div>
            <button className="btn btn-primary mx-1" onClick={addElement}>
              Add
            </button>
            <button className="btn btn-danger mx-1" onClick={clearElement}>
              Clear
            </button>
          </div>
        </div>
        <div className="form-group">
          {Array.from(
            {
              length: index,
            },
            (_, i) => {
              return (
                <Input
                  index={i}
                  data={data}
                  clear={clear}
                  setData={setData}
                  setError={setError}
                  lastIndex={index === i + 1}
                />
              );
            }
          )}
        </div>

        {error && (
          <div className="alert alert-warning" role="alert">
            Choose the component or input the value
          </div>
        )}

        <div className="d-flex justify-content-center py-3">
          <button className="btn btn-success" onClick={handleSubmit}>
            Submit
          </button>
        </div>
      </div>

      <div className="result">
        <h1 className="text-center mb-4">Result</h1>
        <Table bordered>
          <tbody>
            <tr>
              <th>Provided numbers</th>
              <th>Weight</th>
              <th>Propagated numbers</th>
              <th>Status</th>
              <th>Actual cost confident factor</th>
            </tr>
          </tbody>
          <tbody>
            <tr className="first">
              <td>Provided numbers</td>
              <td className="text-end">Weight</td>
              <td>Propagated numbers</td>
              <td>Status</td>
              <td>Actual cost confident factor</td>
            </tr>
            <tr className="second">
              <td>Provided numbers</td>
              <td>Weight</td>
              <td>Propagated numbers</td>
              <td>Status</td>
              <td>Actual cost confident factor</td>
            </tr>
            <tr className="third">
              <td>Provided numbers</td>
              <td>Weight</td>
              <td>Propagated numbers</td>
              <td>Status</td>
              <td>Actual cost confident factor</td>
            </tr>
            <tr className="third">
              <td>Provided numbers</td>
              <td>Weight</td>
              <td>Propagated numbers</td>
              <td>Status</td>
              <td>Actual cost confident factor</td>
            </tr>
            <tr className="second">
              <td>Provided numbers</td>
              <td>Weight</td>
              <td>Propagated numbers</td>
              <td>Status</td>
              <td>Actual cost confident factor</td>
            </tr>
            <tr className="third">
              <td>Provided numbers</td>
              <td>Weight</td>
              <td>Propagated numbers</td>
              <td>Status</td>
              <td>Actual cost confident factor</td>
            </tr>
            <tr className="fourth">
              <td>Provided numbers</td>
              <td>Weight</td>
              <td>Propagated numbers</td>
              <td>Status</td>
              <td>Actual cost confident factor</td>
            </tr>
            <tr className="fifth">
              <td>Provided numbers</td>
              <td>Weight</td>
              <td>Propagated numbers</td>
              <td>Status</td>
              <td>Actual cost confident factor</td>
            </tr>
          </tbody>
        </Table>
      </div>
    </div>
  );
};

export default Dashboard;
