import type { ActionFunctionArgs, MetaFunction } from "@remix-run/node";
import { Form } from "@remix-run/react";

import 'bootstrap/dist/css/bootstrap.min.css';
import { Button, FormControl, FormLabel, FormSelect } from "react-bootstrap";

export const meta: MetaFunction = () => {
  return [
    { title: "Meilleur taux" },
    { name: "description", content: "Welcome to Meilleur Taux!" },
    { charset: "utf-8" },
    { viewport: "width=device-width,initial-scale=1" }
  ];
}

export async function action({request}: ActionFunctionArgs) {
  const body = await request.formData();
  return body;
}

export default function Index() {
  return (
    <div className="container">
      <div className="card">
        <div className="card-body">
          <Form method="POST">
            <div className="row">
              <h1>Trouver le meilleur taux</h1>
              <div className="col-md-6 mb-3">
                <FormLabel>Montant du prêt</FormLabel>
                <FormSelect name="amount" id="name" required>
                  <option></option>
                  <option value="50000">50K</option>
                  <option value="100000">100K</option>
                  <option value="200000">200K</option>
                  <option value="500000">500K</option>
                </FormSelect>
              </div>
              
              <div className="col-md-6 mb-3">
                <FormLabel>Durée du prêt en années</FormLabel>
                <FormSelect name="duration" id="duration" required>
                  <option></option>
                  <option value="15">15 ans</option>
                  <option value="20">20 ans</option>
                  <option value="25">25 ans</option>
                </FormSelect>
              </div>
              
              <div className="col-md-6 mb-3">
                <FormLabel>Nom</FormLabel>
                <FormControl type="text" name="name" id="name" required />
              </div>
              
              <div className="col-md-6 mb-3">
                <FormLabel>Email</FormLabel>
                <FormControl type="email" name="email" id="email" required />
              </div>
              
              <div className="col-md-6 mb-3">
                <FormLabel>Téléphone</FormLabel>
                <FormControl type="text" name="telephone" id="telephone" required />
              </div>
            </div>
            
            <div className="row">
              <div className="col-md-6 mb-3">
                <Button type="submit" variant="primary" className="me-1">Calculer</Button>
                <Button type="button" variant="primary">Recommencer</Button>
              </div>
            </div>
          </Form>
        </div>
      </div>
      
      <div className="row">
        <div className="col-md-12">
          
        </div>
      </div>
      
    </div>
  );
}
