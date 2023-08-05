import { atom } from "jotai";
import { User } from "../interfaces/user";

export const meAtom = atom<User>({
  break_time: "",
  closing_time: "",
  email: "",
  family_name: "",
  given_name: "",
  role_id: "",
  start_time: "",
});
