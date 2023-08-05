import { Link as RouterLink, useNavigate } from "react-router-dom";
import Form from "@rjsf/antd";
import { customizeValidator } from "@rjsf/validator-ajv8";
import japaneseLocalizer from "ajv-i18n/localize/ja";
import { Button, Divider, Space } from "antd";
import { useEffect, useState } from "react";
import axios from "axios";

function SignIn() {
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
      const response = await axios.get("/app/schemas/signin.json");
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
          .post("/app/api/sign/in", formData)
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
          ログイン
        </Button>
        <div style={{ width: "100%", textAlign: "center" }}>
          <RouterLink to={"/sign/up"}>パスワードを忘れた方はこちら</RouterLink>
        </div>
        <Divider style={{ margin: 0 }}>又は</Divider>
        <RouterLink to={"/sign/up"}>
          <Button type={"default"} style={{ width: "100%" }}>
            新規登録
          </Button>
        </RouterLink>
      </Space>
    </Form>
  );
}

export default SignIn;
