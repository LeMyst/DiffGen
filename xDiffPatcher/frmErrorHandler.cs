using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;

namespace xDiffPatcher
{
    public partial class frmErrorHandler : Form
    {
        public frmErrorHandler()
        {
            InitializeComponent();
        }

        private void button1_Click(object sender, EventArgs e)
        {

        }

        //Exit
        private void button2_Click(object sender, EventArgs e)
        {
            Application.Exit();
        }
    }
}
