import type { ActionFunctionArgs, MetaFunction } from "@remix-run/node";
import { Form, json, useLoaderData } from "@remix-run/react";

import 'bootstrap/dist/css/bootstrap.min.css';
import { Button, FormControl, FormLabel, FormSelect, Table } from "react-bootstrap";

export const meta: MetaFunction = () => {
  return [
    { title: "Meilleur taux" },
    { name: "description", content: "Welcome to Meilleur Taux!" },
    { charset: "utf-8" },
    { viewport: "width=device-width,initial-scale=1" }
  ];
}

export async function action({request}: ActionFunctionArgs) {
  const formData = await request.formData();
  try {
    // Prepare the data to be sent in the POST request
    const requestData = {
        amount: formData.get("amount"),
        duration: formData.get("duration")
    }
    
    // Send a POST request to the rate-calculator endpoint
    const response = await fetch("http://127.0.0.1:8000/rate-calculator", {
      method: "POST",
      headers: {
          'Content-Type': 'application/json' // Set content type to JSON
      },
      body: JSON.stringify(requestData) // Convert the data to a JSON string
    });
    
    console.log(response)
    
    // Check if the response is successful
    if (!response.ok) {
      const errorData = await response.json();
      return json(
          { errors: { rateCalculator: errorData.message || "Failed to fetch data." } },
          { status: response.status }
      );
    }
  
    // Parse the response data
    const rateCalculatorData = await response.json();
  
    // Return the rate calculator data or any other relevant response
    return json(rateCalculatorData, { status: 200 });
  } catch (err) {
    return err;
  }
}

export default function Index() {
  const data = useLoaderData();
  
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
        {data && data.length > 0 && (
          <Table striped bordered hover>
            <thead>
              <tr>
                <th>Nom de la banque</th>
                <th>Montant du prêt</th>
                <th>Durée du prêt</th>
                <th>Taux du prêt</th>
              </tr>
            </thead>
            <tbody>
            {data.map((item, index) => (
              <tr key={index}>
                <td>{item.bank}</td>
                <td>{item.amount}</td>
                <td>{item.duration}</td>
                <td>{item.rate}</td>
              </tr>
            ))}
            </tbody>
          </Table>
        )}
        
        {data && data.length === 0 && ( // Handle case when data is empty
            <p>Aucune donnee disponible.</p>
          )}
        </div>
      </div>
      
    </div>
  );
}
