import _ from "lodash";
import React, { useEffect, useState } from "react";
import { Table } from "reactstrap";

import Http from "../Http";
import Input from "../components/Input";

const api = "/api/v1/bid";

const color = ["first", "second", "third", "fourth", "fifth"];

const Dashboard = () => {
  const [clear, setClear] = useState(false);
  const [index, setIndex] = useState(1);
  const [data, setData] = useState([]);
  const [error, setError] = useState(false);
  const [originData, setOriginData] = useState([]);
  const [resultData, setResultData] = useState([]);

  useEffect(() => {
    Http.get(api)
      .then((response) => {
        setOriginData(response.data);
      })
      .catch(() => {
        console.log("Unable to fetch data.");
      });
  }, []);

  const getDepth = (obj) => {
    var depth = 0;
    if (obj.children) {
      obj.children.forEach(function (d) {
        var tmpDepth = getDepth(d);
        if (tmpDepth > depth) {
          depth = tmpDepth;
        }
      });
    }
    return 1 + depth;
  };

  const traverse = (
    node,
    flattened = [
      {
        ...node,
        level: 4,
      },
    ]
  ) => {
    node.children?.map((child) => {
      flattened.push({
        ...child,
        level: getDepth(node) - 2,
      });
      traverse(child, flattened);
    });
    return flattened;
  };

  const originalBid = traverse(originData);

  const options = _.map(originalBid, (itm) => {
    return { value: itm.ref_id, label: itm.title };
  });

  const addElement = () => {
    if (!data[index - 1]?.value) {
      setError(true);
    } else {
      setIndex((prevIndex) => prevIndex + 1);
    }
  };

  const removeElement = () => {
    setIndex((prevIndex) => prevIndex - 1);
    setError(false);
    if (data.length === index) {
      data.pop();
      setData(data);
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
      const metaData = {
        data: JSON.stringify(data),
      };
      Http.post(api, metaData)
        .then((res) => {
          setResultData(traverse(res.data));
        })
        .catch((err) => {
          console.log(err);
        });
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
            <button className="btn btn-secondary mx-1" onClick={removeElement}>
              Back
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
                  options={options}
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
        <div className="row">
          <div className="col-md-4">
            <h1 className="original text-center mb-4">Original bid</h1>
            <Table bordered>
              <tbody>
                <tr>
                  <th>No</th>
                  <th></th>
                  <th>Original Bid Cost</th>
                </tr>
              </tbody>
              <tbody>
                {_.map(originalBid, (itm, key) => {
                  return (
                    <tr
                      key={key}
                      className={`${color[itm.level]} ${
                        itm.type === "line_item" && "purple"
                      }`}
                    >
                      <td className="col-md-1">{key + 1}</td>
                      <td className="col-md-6 text-end">{itm.title}</td>
                      <td className="col-md-5 text-end">${itm.cost}</td>
                    </tr>
                  );
                })}
              </tbody>
            </Table>
          </div>
          <div className="col-md-8">
            <h1 className="text-center mb-4">Result</h1>
            <Table bordered>
              <tbody>
                <tr>
                  <th>Provided numbers</th>
                  <th>Propagated numbers</th>
                  <th>Status</th>
                  <th>Actual cost confident factor</th>
                </tr>
              </tbody>
              <tbody>
                {_.map(resultData, (itm, key) => {
                  return (
                    <tr
                      key={key}
                      className={`${color[itm.level]} ${
                        itm.type === "line_item" && "purple"
                      }`}
                    >
                      <td>{itm.factor === 1 && itm.real_cost}</td>
                      <td className="text-end">
                        ${parseFloat(itm.real_cost).toFixed(2)}
                      </td>
                      <td>{itm.status}</td>
                      <td className="text-end">{itm.factor}</td>
                    </tr>
                  );
                })}
              </tbody>
            </Table>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Dashboard;
