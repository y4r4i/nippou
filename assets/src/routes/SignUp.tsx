import Form from "@rjsf/antd";
import { customizeValidator } from "@rjsf/validator-ajv8";
import japaneseLocalizer from "ajv-i18n/localize/ja";
import { Button, Space } from "antd";
import axios from "axios";
import { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";

function SignUp() {
  const navigate = useNavigate();
  const [schema, setSchema] = useState({});
  const validator = customizeValidator({}, japaneseLocalizer);
  const uiSchema = {
    "ui:submitButtonOptions": {
      norender: true,
    },
  };

  useEffect(() => {
    const get_schema = async () => {
      const response = await axios.get("/app/schemas/signup.json");
      setSchema(response.data);
    };
    get_schema().then(() => {});
  }, []);

  return (
    <Form
      autoComplete={"off"}
      schema={schema}
      uiSchema={uiSchema}
      validator={validator}
      showErrorList={false}
      onSubmit={({ formData }) => {
        axios
          .post("/app/api/sign/up", formData)
          .then((value) => {
            navigate("/");
          })
          .catch((reason) => {
            console.log(reason);
          });
      }}
    >
      <Space direction="vertical" style={{ width: "100%" }}>
        <Button htmlType={"submit"} type={"primary"} style={{ width: "100%" }}>
          新規登録
        </Button>
      </Space>
    </Form>
  );
}

export default SignUp;
