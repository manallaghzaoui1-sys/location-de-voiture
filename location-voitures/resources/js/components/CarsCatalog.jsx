import React, { useMemo, useState } from 'react';

function CarsCatalog({ cars, detailsBaseUrl }) {
    const [search, setSearch] = useState('');
    const [fuel, setFuel] = useState('all');
    const [sort, setSort] = useState('newest');

    const fuelOptions = useMemo(() => {
        const set = new Set(cars.map((car) => car.carburant));
        return ['all', ...Array.from(set)];
    }, [cars]);

    const filteredCars = useMemo(() => {
        const keyword = search.trim().toLowerCase();
        let output = cars.filter((car) => {
            const fullName = `${car.marque} ${car.modele}`.toLowerCase();
            const matchSearch = keyword === '' || fullName.includes(keyword);
            const matchFuel = fuel === 'all' || car.carburant === fuel;
            return matchSearch && matchFuel;
        });

        if (sort === 'price_asc') {
            output = [...output].sort((a, b) => a.prix_par_jour - b.prix_par_jour);
        } else if (sort === 'price_desc') {
            output = [...output].sort((a, b) => b.prix_par_jour - a.prix_par_jour);
        } else {
            output = [...output].sort((a, b) => b.id - a.id);
        }

        return output;
    }, [cars, search, fuel, sort]);

    return (
        <div>
            <div className="card shadow-sm border-0 mb-4">
                <div className="card-body">
                    <div className="row g-2">
                        <div className="col-md-5">
                            <input
                                type="text"
                                className="form-control"
                                placeholder="Recherche marque / modèle"
                                value={search}
                                onChange={(event) => setSearch(event.target.value)}
                            />
                        </div>
                        <div className="col-md-3">
                            <select className="form-select" value={fuel} onChange={(event) => setFuel(event.target.value)}>
                                {fuelOptions.map((option) => (
                                    <option key={option} value={option}>
                                        {option === 'all' ? 'Tous les carburants' : option}
                                    </option>
                                ))}
                            </select>
                        </div>
                        <div className="col-md-4">
                            <select className="form-select" value={sort} onChange={(event) => setSort(event.target.value)}>
                                <option value="newest">Plus récents</option>
                                <option value="price_asc">Prix croissant</option>
                                <option value="price_desc">Prix décroissant</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div className="row">
                {filteredCars.length === 0 && (
                    <div className="col-12">
                        <div className="alert alert-info">Aucun véhicule ne correspond à votre filtre.</div>
                    </div>
                )}

                {filteredCars.map((car) => (
                    <div className="col-md-4 mb-4" key={car.id}>
                        <div className="card car-card h-100">
                            <img src={car.image_url} className="card-img-top" alt={`${car.marque} ${car.modele}`} />
                            <div className="card-body">
                                <h5 className="card-title">
                                    {car.marque} {car.modele}
                                </h5>
                                <p className="card-text">
                                    <i className="fas fa-calendar-alt" /> Année: {car.annee}
                                    <br />
                                    <i className="fas fa-gas-pump" /> Carburant: {car.carburant}
                                    <br />
                                    <span className="price-badge">{car.prix_par_jour} DH / jour</span>
                                </p>
                                <a href={`${detailsBaseUrl}/${car.id}`} className="btn btn-primary w-100">
                                    <i className="fas fa-info-circle" /> Voir détails
                                </a>
                            </div>
                        </div>
                    </div>
                ))}
            </div>
        </div>
    );
}

export default CarsCatalog;

