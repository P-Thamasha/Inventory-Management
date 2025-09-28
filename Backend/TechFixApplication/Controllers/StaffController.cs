using Microsoft.AspNetCore.Http;
using Microsoft.AspNetCore.Mvc;
using TechFixApplication.Models;
using TechFixApplication.Models.Entities;
using TechFixApplication.Utili;

namespace TechFixApplication.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class StaffController : ControllerBase
    {
        private readonly DBConnection dBConnection;
        public StaffController(DBConnection dBConnection)
        {
            this.dBConnection = dBConnection;
        }


        [HttpGet]
        public IActionResult GetAllStaff()
        {
            var allStaff = dBConnection.Staff.ToList();

            return Ok(allStaff);

            //return Ok(dBConnection.Employees.ToList());
        }

        [HttpPost]
        public IActionResult AddStaff(AddStaffDto addStaffDto)
        {
            var staffEntity = new Staff()
            {

                Name = addStaffDto.Name,
                Email = addStaffDto.Email,
                Phone = addStaffDto.Phone,
                Password = addStaffDto.Password,
            };

            dBConnection.Staff.Add(staffEntity);
            dBConnection.SaveChanges();

            return Ok(staffEntity);
        }

        [HttpGet]
        [Route("{id:guid}")]
        public IActionResult GetEmployeeById(Guid id)
        {

            var staff = dBConnection.Staff.Find(id);

            if (staff == null)
            {
                return NotFound();
            }

            return Ok(staff);
        }


        [HttpPut]
        [Route("{id:guid}")]
        public IActionResult UpdateStaff(Guid id, UpdateStaffDto updateStaffDto)
        {
            var staff = dBConnection.Staff.Find(id);

            if (staff == null)
            {
                return NotFound();
            }

            staff.Name = updateStaffDto.Name;
            staff.Email = updateStaffDto.Email;
            staff.Phone = updateStaffDto.Phone;
            staff.Password = updateStaffDto.Password;

            dBConnection.SaveChanges();

            return Ok(staff);


        }

        [HttpDelete]
        [Route("{id:guid}")]
        public IActionResult DeleteEmployeee(Guid id)
        {
            var staff = dBConnection.Staff.Find(id);

            if (staff == null)
            {
                return NotFound();
            }

            dBConnection.Staff.Remove(staff);
            dBConnection.SaveChanges();

            return Ok(staff);

        }

        [HttpPost("login")]
        public IActionResult LoginStaff(LoginStaffDto loginDto)
        {
            // Find staff by email and password
            var staff = dBConnection.Staff
                        .FirstOrDefault(s => s.Email == loginDto.Email && s.Password == loginDto.Password);

            if (staff == null)
            {
                return Unauthorized(new { message = "Invalid email or password." });
            }

            return Ok(new
            {
                message = "Login successful!",
                staffId = staff.Id,
                name = staff.Name,
                email = staff.Email
            });
        }

    }

   

    }
