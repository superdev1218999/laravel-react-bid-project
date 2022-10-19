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
  const [originData, setOriginData] = useState([]);
  const [resultData, setResultData] = useState([]);
  const [error, setError] = useState(false);

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

  const clearElement = () => {
    setData([]);
    setIndex(1);
    setClear((prev) => !prev);
  };

  const handleSubmit = () => {
    if (!data[index - 1]?.value) {
      setError(true);
    } else {
      let res = {
        id: 1,
        ref_id: "huk4s55z",
        type: "bid",
        title: "Bid Name",
        cost: 11700,
        components: null,
        line_items: null,
        parent_component_id: null,
        real_cost: 35400,
        factor: 1,
        children: [
          {
            id: 1,
            ref_id: "ajkcs47e",
            type: "component",
            title: "Component 1",
            cost: 10700,
            components: [2, 3],
            line_items: [1],
            parent_component_id: null,
            real_cost: 16700,
            factor: 1,
            children: [
              {
                id: 1,
                ref_id: "d13pen25f",
                type: "line_item",
                title: "Line item 1",
                cost: 100,
                components: null,
                line_items: null,
                parent_component_id: null,
                real_cost: 500,
                factor: 2,
                children: [],
              },
              {
                id: 2,
                ref_id: "ajvfl84j",
                type: "component",
                title: "Component 2",
                cost: 1000,
                components: [],
                line_items: [2, 3, 4],
                parent_component_id: 1,
                real_cost: 1200,
                factor: 1,
                children: [
                  {
                    id: 2,
                    ref_id: "d13fib40k",
                    type: "line_item",
                    title: "Line item 2",
                    cost: 200,
                    components: null,
                    line_items: null,
                    parent_component_id: null,
                    real_cost: 121212,
                    factor: 4,
                    children: [],
                  },
                  {
                    id: 3,
                    ref_id: "d13umo65p",
                    type: "line_item",
                    title: "Line item 3",
                    cost: 300,
                    components: null,
                    line_items: null,
                    parent_component_id: null,
                    real_cost: 121212,
                    factor: 4,
                    children: [],
                  },
                  {
                    id: 4,
                    ref_id: "d13kqc80u",
                    type: "line_item",
                    title: "Line item 4",
                    cost: 500,
                    components: null,
                    line_items: null,
                    parent_component_id: null,
                    real_cost: 121212,
                    factor: 4,
                    children: [],
                  },
                ],
              },
              {
                id: 3,
                ref_id: "ajgie21o",
                type: "component",
                title: "Component 3",
                cost: 9600,
                components: [4, 5],
                line_items: [5, 6],
                parent_component_id: 1,
                real_cost: 15000,
                factor: 1,
                children: [
                  {
                    id: 5,
                    ref_id: "d13aup05a",
                    type: "line_item",
                    title: "Line item 5",
                    cost: 800,
                    components: null,
                    line_items: null,
                    parent_component_id: null,
                    real_cost: 3047.6190476190477,
                    factor: 2,
                    children: [],
                  },
                  {
                    id: 6,
                    ref_id: "d13pyd20f",
                    type: "line_item",
                    title: "Line item 6",
                    cost: 1300,
                    components: null,
                    line_items: null,
                    parent_component_id: null,
                    real_cost: 4952.380952380952,
                    factor: 2,
                    children: [],
                  },
                  {
                    id: 4,
                    ref_id: "ajrlx68t",
                    type: "component",
                    title: "Component 4",
                    cost: 5500,
                    components: [],
                    line_items: [7, 8],
                    parent_component_id: 3,
                    real_cost: 4500,
                    factor: 1,
                    children: [
                      {
                        id: 7,
                        ref_id: "d13fdq45k",
                        type: "line_item",
                        title: "Line item 7",
                        cost: 2100,
                        components: null,
                        line_items: null,
                        parent_component_id: null,
                        real_cost: 121212,
                        factor: 6,
                        children: [],
                      },
                      {
                        id: 8,
                        ref_id: "d13uhe60p",
                        type: "line_item",
                        title: "Line item 8",
                        cost: 3400,
                        components: null,
                        line_items: null,
                        parent_component_id: null,
                        real_cost: 121212,
                        factor: 6,
                        children: [],
                      },
                    ],
                  },
                  {
                    id: 5,
                    ref_id: "ajcoq05y",
                    type: "component",
                    title: "Component 5",
                    cost: 2000,
                    components: [],
                    line_items: [9],
                    parent_component_id: 3,
                    real_cost: 2500,
                    factor: 1,
                    children: [
                      {
                        id: 9,
                        ref_id: "d13klr85u",
                        type: "line_item",
                        title: "Line item 9",
                        cost: 2000,
                        components: null,
                        line_items: null,
                        parent_component_id: null,
                        real_cost: 121212,
                        factor: 7,
                        children: [],
                      },
                    ],
                  },
                ],
              },
            ],
          },
          {
            id: 6,
            ref_id: "ajnrj42d",
            type: "component",
            title: "Component 6",
            cost: 1000,
            components: [],
            line_items: [10],
            parent_component_id: null,
            real_cost: 18700,
            factor: 2,
            children: [
              {
                id: 10,
                ref_id: "d13apf00a",
                type: "line_item",
                title: "Line item 10",
                cost: 1000,
                components: null,
                line_items: null,
                parent_component_id: null,
                real_cost: 121212,
                factor: 8,
                children: [],
              },
            ],
          },
        ],
      };
      setResultData(traverse(res));
      // Http.post("/api/v1/auth/register", credentials)
      //   .then((res) => resolve(res.data))
      //   .catch((err) => {
      //     const { status, errors } = err.response.data;
      //     const data = {
      //       status,
      //       errors,
      //     };
      //     return reject(data);
      //   });
    }
  };

  const test = (e) => {
    console.log(originalBid);
  };

  return (
    <div className="container py-5">
      <div className="mb-5">
        <h1 className="text-center mb-4">Add a Initial "Bid"</h1>
        <div className="d-flex align-items-center justify-content-between">
          <h5>Create Initial "Bid" to component or line item you want.</h5>
          <div>
            <button className="btn btn-primary mx-1" onClick={test}>
              Addsss
            </button>
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
                  <th>Weight</th>
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
                      <td></td>
                      <td className="text-end">0.02</td>
                      <td className="text-end">${itm.real_cost}</td>
                      <td>${itm.cost}</td>
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
